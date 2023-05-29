function Span(span)
if span.classes:includes('personaldata') and span.classes:includes('racialorigin') then
  span.content = pandoc.Str('xxxxxx')
  local value, position = span.classes:find('racialorigin')
  span.classes:remove(position)
end
return span
end