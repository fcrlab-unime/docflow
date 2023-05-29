function Span(span)
if span.classes:includes('personaldata') and span.classes:includes('ethnicity') then
  span.content = pandoc.Str('xxxxxx')
  local value, position = span.classes:find('ethnicity')
  span.classes:remove(position)
end
return span
end