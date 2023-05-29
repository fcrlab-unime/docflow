function Span(span)
if span.classes:includes('personaldata') and span.classes:includes('geneticdata') then
  span.content = pandoc.Str('xxxxxx')
  local value, position = span.classes:find('geneticdata')
  span.classes:remove(position)
end
return span
end